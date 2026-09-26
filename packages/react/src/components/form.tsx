import {
  type ChangeEvent,
  type CSSProperties,
  type FormEvent,
  type InputHTMLAttributes,
  type KeyboardEvent,
  type ReactNode,
  type SelectHTMLAttributes,
  type TextareaHTMLAttributes,
  createContext,
  forwardRef,
  useContext,
  useEffect,
  useId,
  useRef,
  useState,
} from 'react';
import { type IconName, autogrow, icons } from '@nabuxai/ui-core';
import { cx, mergeRefs, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useT } from '../internal/provider';

/* ---- Field: label, hint and error wired to the control inside ------------------ */

interface FieldContextValue {
  id: string;
  hintId?: string;
  errorId?: string;
  invalid: boolean;
  required?: boolean;
  disabled?: boolean;
}

const FieldContext = createContext<FieldContextValue | null>(null);

/** The id and aria wiring of the surrounding <Field>, merged with the control's own props. */
function useFieldProps<P extends { id?: string; 'aria-describedby'?: string; 'aria-invalid'?: unknown; required?: boolean; disabled?: boolean }>(props: P, invalidProp?: boolean) {
  const field = useContext(FieldContext);
  const describedBy = [props['aria-describedby'], field?.hintId, field?.errorId].filter(Boolean).join(' ') || undefined;
  const invalid = invalidProp || field?.invalid;
  return {
    id: props.id ?? field?.id,
    'aria-describedby': describedBy,
    'aria-invalid': invalid ? (true as const) : (props['aria-invalid'] as undefined),
    required: props.required ?? field?.required,
    disabled: props.disabled ?? field?.disabled,
  };
}

export interface FieldProps {
  label?: ReactNode;
  hint?: ReactNode;
  /** An error message; also marks the control invalid. */
  error?: ReactNode;
  required?: boolean;
  disabled?: boolean;
  /** The control's id (generated when omitted). */
  id?: string;
  className?: string;
  style?: CSSProperties;
  children: ReactNode;
}

export function Field({ label, hint, error, required, disabled, id, className, style, children }: FieldProps) {
  const generated = useId();
  const controlId = id ?? `nx-field${generated.replace(/:/g, '')}`;
  const hintId = hint ? `${controlId}-hint` : undefined;
  const errorId = error ? `${controlId}-error` : undefined;

  return (
    <FieldContext.Provider value={{ id: controlId, hintId, errorId, invalid: !!error, required, disabled }}>
      <div className={cx('nx-field', className)} data-invalid={error ? '' : undefined} style={style}>
        {label && (
          <label className="nx-label" htmlFor={controlId}>
            {label}
            {required && (
              <span className="nx-label-required" aria-hidden="true">
                *
              </span>
            )}
          </label>
        )}
        {hint && (
          <p className="nx-hint" id={hintId}>
            {hint}
          </p>
        )}
        {children}
        {error && (
          <p className="nx-error" id={errorId}>
            <Icon name="alert-circle" />
            {error}
          </p>
        )}
      </div>
    </FieldContext.Provider>
  );
}

/* ---- Input ----------------------------------------------------------------------- */

/** A known icon name becomes the icon; any other string or node is shown as is ("kg", "https://"). */
const addon = (value: IconName | ReactNode) => (typeof value === 'string' && value in icons ? <Icon name={value as IconName} /> : value);

export interface InputProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'size'> {
  size?: 'sm' | 'md' | 'lg';
  invalid?: boolean;
  /** Content before the text: an icon name, a unit, a prefix. */
  startAddon?: IconName | ReactNode;
  endAddon?: IconName | ReactNode;
}

export const Input = forwardRef<HTMLInputElement, InputProps>(function Input({ size, invalid, startAddon, endAddon, className, ...rest }, ref) {
  const wiring = useFieldProps(rest, invalid);
  const input = <input ref={ref} className={cx('nx-input', !startAddon && !endAddon && className)} data-size={size === 'md' ? undefined : size} {...rest} {...wiring} />;
  if (!startAddon && !endAddon) return input;

  return (
    <div className={cx('nx-input-group', className)} data-size={size === 'md' ? undefined : size}>
      {startAddon && <span className="nx-input-addon">{addon(startAddon)}</span>}
      {input}
      {endAddon && <span className="nx-input-addon">{addon(endAddon)}</span>}
    </div>
  );
});

export interface TextareaProps extends TextareaHTMLAttributes<HTMLTextAreaElement> {
  invalid?: boolean;
  /** Grow with the content up to this many lines. */
  maxRows?: number;
}

export const Textarea = forwardRef<HTMLTextAreaElement, TextareaProps>(function Textarea({ invalid, maxRows, className, style, ...rest }, ref) {
  const own = useRef<HTMLTextAreaElement>(null);
  const wiring = useFieldProps(rest, invalid);
  useEffect(() => (own.current ? autogrow(own.current) : undefined), []);

  return (
    <textarea
      ref={mergeRefs(own, ref)}
      className={cx('nx-textarea', className)}
      style={{ ...(maxRows ? { '--nx-textarea-max': `${maxRows}lh` } : null), ...style } as CSSProperties}
      {...rest}
      {...wiring}
    />
  );
});

export interface SelectOption {
  value: string;
  label: string;
  disabled?: boolean;
}

export interface SelectProps extends Omit<SelectHTMLAttributes<HTMLSelectElement>, 'size'> {
  size?: 'sm' | 'md' | 'lg';
  invalid?: boolean;
  options?: SelectOption[];
  placeholder?: string;
}

export const Select = forwardRef<HTMLSelectElement, SelectProps>(function Select({ size, invalid, options, placeholder, className, children, ...rest }, ref) {
  const wiring = useFieldProps(rest, invalid);
  return (
    <select ref={ref} className={cx('nx-select', className)} data-size={size === 'md' ? undefined : size} {...rest} {...wiring}>
      {placeholder && (
        <option value="" disabled>
          {placeholder}
        </option>
      )}
      {options?.map((option) => (
        <option key={option.value} value={option.value} disabled={option.disabled}>
          {option.label}
        </option>
      ))}
      {children}
    </select>
  );
});

/* ---- Choices --------------------------------------------------------------------- */

interface ChoiceOwnProps {
  label?: ReactNode;
  description?: ReactNode;
  invalid?: boolean;
}

export interface CheckboxProps extends ChoiceOwnProps, Omit<InputHTMLAttributes<HTMLInputElement>, 'type'> {
  indeterminate?: boolean;
  onCheckedChange?: (checked: boolean) => void;
}

function ChoiceShell({ control, label, description, className, style }: { control: ReactNode; label?: ReactNode; description?: ReactNode; className?: string; style?: CSSProperties }) {
  if (!label && !description) return <>{control}</>;
  return (
    <label className={cx('nx-choice', className)} style={style}>
      {control}
      <span className="nx-choice-text">
        {label && <span className="nx-choice-label">{label}</span>}
        {description && <span className="nx-choice-description">{description}</span>}
      </span>
    </label>
  );
}

export const Checkbox = forwardRef<HTMLInputElement, CheckboxProps>(function Checkbox({ label, description, indeterminate, invalid, onCheckedChange, onChange, className, style, ...rest }, ref) {
  const own = useRef<HTMLInputElement>(null);
  const wiring = useFieldProps(rest, invalid);
  useEffect(() => {
    if (own.current) own.current.indeterminate = !!indeterminate;
  }, [indeterminate]);

  const control = (
    <input
      ref={mergeRefs(own, ref)}
      type="checkbox"
      className={cx('nx-checkbox', !label && !description && className)}
      onChange={(event) => {
        onChange?.(event);
        onCheckedChange?.(event.target.checked);
      }}
      {...rest}
      {...wiring}
    />
  );
  return <ChoiceShell control={control} label={label} description={description} className={className} style={style} />;
});

export interface SwitchProps extends ChoiceOwnProps, Omit<InputHTMLAttributes<HTMLInputElement>, 'type' | 'size'> {
  size?: 'sm' | 'md' | 'lg';
  onCheckedChange?: (checked: boolean) => void;
}

export const Switch = forwardRef<HTMLInputElement, SwitchProps>(function Switch({ label, description, size, invalid, onCheckedChange, onChange, className, style, ...rest }, ref) {
  const wiring = useFieldProps(rest, invalid);
  const control = (
    <input
      ref={ref}
      type="checkbox"
      role="switch"
      className={cx('nx-switch', !label && !description && className)}
      data-size={size === 'md' ? undefined : size}
      onChange={(event) => {
        onChange?.(event);
        onCheckedChange?.(event.target.checked);
      }}
      {...rest}
      {...wiring}
    />
  );
  return <ChoiceShell control={control} label={label} description={description} className={className} style={style} />;
});

export interface RadioProps extends ChoiceOwnProps, Omit<InputHTMLAttributes<HTMLInputElement>, 'type'> {}

export const Radio = forwardRef<HTMLInputElement, RadioProps>(function Radio({ label, description, invalid, className, style, ...rest }, ref) {
  const control = <input ref={ref} type="radio" className={cx('nx-radio', !label && !description && className)} aria-invalid={invalid || undefined} {...rest} />;
  return <ChoiceShell control={control} label={label} description={description} className={className} style={style} />;
});

export interface RadioGroupProps {
  name: string;
  legend?: ReactNode;
  options: Array<{ value: string; label: ReactNode; description?: ReactNode; disabled?: boolean }>;
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  orientation?: 'vertical' | 'horizontal';
  className?: string;
}

export function RadioGroup({ name, legend, options, value, defaultValue = '', onValueChange, orientation = 'vertical', className }: RadioGroupProps) {
  const [current, setCurrent] = useControllable(value, defaultValue, onValueChange);
  return (
    <fieldset className={cx('nx-field', className)} style={{ border: 0, margin: 0, padding: 0 }}>
      {legend && <legend className="nx-label">{legend}</legend>}
      <div style={{ display: 'flex', flexDirection: orientation === 'vertical' ? 'column' : 'row', flexWrap: 'wrap', gap: 'var(--nx-space-3) var(--nx-space-6)' }}>
        {options.map((option) => (
          <Radio
            key={option.value}
            name={name}
            value={option.value}
            label={option.label}
            description={option.description}
            disabled={option.disabled}
            checked={current === option.value}
            onChange={() => setCurrent(option.value)}
          />
        ))}
      </div>
    </fieldset>
  );
}

/* ---- One-time code --------------------------------------------------------------- */

export interface OtpInputProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'value' | 'defaultValue' | 'onChange' | 'maxLength' | 'size'> {
  length?: number;
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  /** Called once every slot is filled. */
  onComplete?: (value: string) => void;
  /** Draw a dash after this many slots (3 gives 123–456). */
  separatorAfter?: number;
  invalid?: boolean;
  /** Accept letters as well as digits. */
  alphanumeric?: boolean;
}

export const OtpInput = forwardRef<HTMLInputElement, OtpInputProps>(function OtpInput(
  { length = 6, value, defaultValue = '', onValueChange, onComplete, separatorAfter, invalid, alphanumeric, className, ...rest },
  ref,
) {
  const t = useT();
  const [code, setCode] = useControllable(value, defaultValue, onValueChange);
  const [focused, setFocused] = useState(false);
  const wiring = useFieldProps(rest, invalid);
  const pattern = alphanumeric ? /[^0-9a-z]/gi : /[^0-9۰-۹٠-٩]/g;

  const normalise = (raw: string) =>
    raw
      .replace(pattern, '')
      // Persian and Arabic-Indic digits type as themselves; store ASCII.
      .replace(/[۰-۹]/g, (d) => String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d)))
      .replace(/[٠-٩]/g, (d) => String('٠١٢٣٤٥٦٧٨٩'.indexOf(d)))
      .slice(0, length);

  const onChange = (event: ChangeEvent<HTMLInputElement>) => {
    const next = normalise(event.target.value);
    setCode(next);
    if (next.length === length) onComplete?.(next);
  };

  const active = Math.min(code.length, length - 1);

  return (
    <div className={cx('nx-otp', className)} data-invalid={invalid || wiring['aria-invalid'] ? '' : undefined}>
      <input
        ref={ref}
        className="nx-otp-input"
        value={code}
        onChange={onChange}
        onFocus={() => setFocused(true)}
        onBlur={() => setFocused(false)}
        inputMode={alphanumeric ? 'text' : 'numeric'}
        autoComplete="one-time-code"
        maxLength={length}
        aria-label={rest['aria-label'] ?? (rest['aria-labelledby'] ? undefined : t('code'))}
        spellCheck={false}
        {...rest}
        {...wiring}
      />
      <div className="nx-otp-slots" aria-hidden="true">
        {Array.from({ length }, (_, i) => (
          <span key={i} style={{ display: 'contents' }}>
            <span className="nx-otp-slot" data-filled={code[i] ? '' : undefined} data-active={focused && i === active ? '' : undefined}>
              {code[i] ? <span key={code[i] + i}>{code[i]}</span> : null}
            </span>
            {separatorAfter && i === separatorAfter - 1 && i < length - 1 ? <span className="nx-otp-sep" /> : null}
          </span>
        ))}
      </div>
    </div>
  );
});

/* ---- Slider ------------------------------------------------------------------------ */

export interface SliderProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type' | 'value' | 'defaultValue' | 'onChange' | 'min' | 'max' | 'step'> {
  min?: number;
  max?: number;
  step?: number;
  value?: number;
  defaultValue?: number;
  onValueChange?: (value: number) => void;
  /** Keep the value bubble visible, not only while interacting. */
  showValue?: boolean;
  formatValue?: (value: number) => string;
  /** Labels under the track's ends. */
  startLabel?: ReactNode;
  endLabel?: ReactNode;
}

export const Slider = forwardRef<HTMLInputElement, SliderProps>(function Slider(
  { min = 0, max = 100, step = 1, value, defaultValue, onValueChange, showValue, formatValue, startLabel, endLabel, className, style, ...rest },
  ref,
) {
  const [current, setCurrent] = useControllable(value, defaultValue ?? min, onValueChange);
  const wiring = useFieldProps(rest);
  const fraction = (current - min) / (max - min || 1);
  const shown = formatValue ? formatValue(current) : String(current);

  return (
    <div
      className={cx('nx-slider', className)}
      data-show-value={showValue ? '' : undefined}
      style={{ '--nx-pct': `${fraction * 100}%`, '--nx-frac': fraction, ...style } as CSSProperties}
    >
      <input
        ref={ref}
        type="range"
        className="nx-slider-input"
        min={min}
        max={max}
        step={step}
        value={current}
        aria-valuetext={formatValue ? shown : undefined}
        onChange={(event) => setCurrent(Number(event.target.value))}
        {...rest}
        {...wiring}
      />
      <output className="nx-slider-value" aria-hidden="true">
        {shown}
      </output>
      {(startLabel || endLabel) && (
        <div className="nx-slider-labels" aria-hidden="true">
          <span>{startLabel}</span>
          <span>{endLabel}</span>
        </div>
      )}
    </div>
  );
});

/* ---- File drop ------------------------------------------------------------------- */

export interface FileItem {
  name: string;
  size?: number;
  /** 0–100 while uploading. */
  progress?: number;
  status?: 'uploading' | 'done' | 'error';
}

export interface FileDropProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type' | 'onChange' | 'title'> {
  onFiles?: (files: File[]) => void;
  /** Files to list under the drop zone (with their upload progress). */
  files?: FileItem[];
  title?: ReactNode;
  hint?: ReactNode;
}

const formatSize = (bytes?: number) => {
  if (bytes === undefined) return '';
  const units = ['B', 'KB', 'MB', 'GB'];
  let i = 0;
  let size = bytes;
  while (size >= 1024 && i < units.length - 1) {
    size /= 1024;
    i += 1;
  }
  return `${size.toFixed(size < 10 && i > 0 ? 1 : 0)} ${units[i]}`;
};

export const FileDrop = forwardRef<HTMLInputElement, FileDropProps>(function FileDrop({ onFiles, files, title, hint, className, disabled, ...rest }, ref) {
  const t = useT();
  const [dragging, setDragging] = useState(false);
  const depth = useRef(0);

  const take = (list: FileList | null) => {
    const picked = list ? Array.from(list) : [];
    if (picked.length) onFiles?.(picked);
  };

  return (
    <div className={className}>
      <label
        className="nx-filedrop"
        data-dragging={dragging ? '' : undefined}
        onDragEnter={(event) => {
          event.preventDefault();
          if (disabled) return;
          depth.current += 1;
          setDragging(true);
        }}
        onDragOver={(event) => event.preventDefault()}
        onDragLeave={() => {
          depth.current = Math.max(0, depth.current - 1);
          if (depth.current === 0) setDragging(false);
        }}
        onDrop={(event) => {
          event.preventDefault();
          depth.current = 0;
          setDragging(false);
          if (!disabled) take(event.dataTransfer.files);
        }}
      >
        <input ref={ref} type="file" className="nx-visually-hidden" disabled={disabled} onChange={(event) => take(event.target.files)} {...rest} />
        <span className="nx-filedrop-icon" aria-hidden="true">
          <Icon name="upload" />
        </span>
        <span className="nx-filedrop-title">
          {title ?? <DropTitle template={t('dropFiles', { browse: '{browse}' })} browse={t('browse')} />}
        </span>
        {hint && <span className="nx-filedrop-hint">{hint}</span>}
      </label>

      {files && files.length > 0 && (
        <ul className="nx-file-list">
          {files.map((file, i) => (
            <li key={`${file.name}-${i}`} className="nx-file" data-status={file.status} style={{ '--nx-i': i } as CSSProperties}>
              <Icon name="file" />
              <span className="nx-file-name">{file.name}</span>
              <span className="nx-file-state" aria-label={file.status ? t(file.status === 'done' ? 'uploaded' : file.status === 'error' ? 'failed' : 'uploading') : undefined}>
                {file.status === 'done' ? <Icon name="check-circle" /> : file.status === 'error' ? <Icon name="alert-circle" /> : null}
              </span>
              <span className="nx-file-meta">{formatSize(file.size)}</span>
              {file.status === 'uploading' && (
                <progress className="nx-progress" value={file.progress ?? 0} max={100} style={{ '--nx-value': file.progress ?? 0 } as CSSProperties} aria-label={`${t('uploading')} ${file.name}`} />
              )}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
});

/** "Drop files here or {browse}" with the word "browse" underlined, in any language order. */
function DropTitle({ template, browse }: { template: string; browse: string }) {
  const [before, after] = template.split('{browse}');
  return (
    <>
      {before}
      <u>{browse}</u>
      {after}
    </>
  );
}

/* ---- Prompt input ------------------------------------------------------------------ */

export interface PromptInputProps {
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  onSubmit?: (value: string) => void;
  placeholder?: string;
  /** While a reply is streaming the send button becomes a stop button. */
  streaming?: boolean;
  onStop?: () => void;
  /** Attached files shown as chips above the text. */
  attachments?: Array<{ id: string; name: string }>;
  onRemoveAttachment?: (id: string) => void;
  /** Offer the paperclip; called with the chosen files. */
  onAttach?: (files: File[]) => void;
  /** Extra controls in the toolbar (model picker, toggles). */
  toolbar?: ReactNode;
  /** An aurora glow around the composer while it has focus. */
  glow?: boolean;
  maxRows?: number;
  disabled?: boolean;
  name?: string;
  className?: string;
  'aria-label'?: string;
}

export function PromptInput({
  value,
  defaultValue = '',
  onValueChange,
  onSubmit,
  placeholder,
  streaming,
  onStop,
  attachments,
  onRemoveAttachment,
  onAttach,
  toolbar,
  glow = true,
  maxRows = 12,
  disabled,
  name,
  className,
  'aria-label': ariaLabel,
}: PromptInputProps) {
  const t = useT();
  const [text, setText] = useControllable(value, defaultValue, onValueChange);
  const area = useRef<HTMLTextAreaElement>(null);
  const picker = useRef<HTMLInputElement>(null);
  useEffect(() => (area.current ? autogrow(area.current) : undefined), []);

  const submit = (event?: FormEvent) => {
    event?.preventDefault();
    if (streaming) {
      onStop?.();
      return;
    }
    const trimmed = text.trim();
    if (!trimmed || disabled) return;
    onSubmit?.(trimmed);
  };

  const onKeyDown = (event: KeyboardEvent<HTMLTextAreaElement>) => {
    if (event.key === 'Enter' && !event.shiftKey && !event.nativeEvent.isComposing) submit(event);
  };

  return (
    <form className={cx('nx-prompt', className)} data-glow={glow ? '' : undefined} onSubmit={submit}>
      {attachments && attachments.length > 0 && (
        <div className="nx-prompt-attachments">
          {attachments.map((file) => (
            <Chip key={file.id} onRemove={onRemoveAttachment ? () => onRemoveAttachment(file.id) : undefined}>
              {file.name}
            </Chip>
          ))}
        </div>
      )}
      <textarea
        ref={area}
        className="nx-prompt-textarea"
        rows={1}
        name={name}
        value={text}
        placeholder={placeholder}
        aria-label={ariaLabel ?? placeholder}
        disabled={disabled}
        onChange={(event) => setText(event.target.value)}
        onKeyDown={onKeyDown}
        style={{ '--nx-prompt-max': `${maxRows}lh` } as CSSProperties}
      />
      <div className="nx-prompt-toolbar">
        {onAttach && (
          <>
            <input ref={picker} type="file" multiple hidden onChange={(event) => event.target.files && onAttach(Array.from(event.target.files))} />
            <button type="button" className="nx-button" data-variant="ghost" data-size="sm" data-icon-only="" aria-label={t('attach')} onClick={() => picker.current?.click()}>
              <span className="nx-button-label">
                <Icon name="paperclip" />
              </span>
            </button>
          </>
        )}
        {toolbar}
        <span className="nx-prompt-spacer" />
        <button
          type="submit"
          className="nx-prompt-send"
          data-state={streaming ? 'streaming' : text.trim() ? 'ready' : undefined}
          disabled={!streaming && (!text.trim() || disabled)}
          aria-label={streaming ? t('stop') : t('send')}
        >
          <Icon name="arrow-up" className="nx-prompt-arrow" />
          <Icon name="stop" className="nx-prompt-stop" />
        </button>
      </div>
    </form>
  );
}

/* ---- Chip --------------------------------------------------------------------------- */

export interface ChipProps {
  children: string;
  onRemove?: () => void;
  className?: string;
}

export function Chip({ children, onRemove, className }: ChipProps) {
  const t = useT();
  return (
    <span className={cx('nx-chip', className)}>
      <span>{children}</span>
      {onRemove && (
        <button type="button" className="nx-chip-remove" aria-label={t('remove', { name: children })} onClick={onRemove}>
          <Icon name="x" />
        </button>
      )}
    </span>
  );
}
