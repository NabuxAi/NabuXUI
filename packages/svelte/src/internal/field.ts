/**
 * The wiring between a Field and the control inside it — the Svelte
 * counterpart of ui-react's FieldContext/useFieldProps: the Field seat
 * provides id/aria data through Svelte context (a getter object, so it stays
 * live as error/hint appear) and every control merges it with its own props.
 */
import { getContext } from 'svelte';

export interface NxField {
  /** The id the surrounding Field gave the control (generated when omitted). */
  id: string;
  hintId?: string;
  errorId?: string;
  invalid: boolean;
  required?: boolean;
  disabled?: boolean;
}

/** Read the surrounding Field's wiring — call once during initialisation. */
export function useField(): NxField | undefined {
  return getContext<NxField | undefined>('nx-field');
}

/** The id and aria wiring of the surrounding Field, merged with the control's own props. */
export function fieldProps<
  P extends { id?: string | null; 'aria-describedby'?: string | null; 'aria-invalid'?: unknown; required?: boolean | null; disabled?: boolean | null },
>(
  props: P,
  field: NxField | undefined,
  invalidProp?: boolean,
): { id: string | undefined; 'aria-describedby': string | undefined; 'aria-invalid'?: true; required?: boolean; disabled?: boolean } {
  const describedBy = [props['aria-describedby'], field?.hintId, field?.errorId].filter(Boolean).join(' ') || undefined;
  const invalid = invalidProp || field?.invalid;
  const ownInvalid = props['aria-invalid'] as boolean | undefined;
  return {
    id: props.id ?? field?.id ?? undefined,
    'aria-describedby': describedBy,
    'aria-invalid': invalid ? true : ownInvalid || undefined,
    required: props.required ?? field?.required,
    disabled: props.disabled ?? field?.disabled,
  };
}
