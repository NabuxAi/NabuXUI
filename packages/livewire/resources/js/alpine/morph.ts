/**
 * A morph catch-up that actually fires. The DOM event `livewire:morph.updated`
 * never arrives in Livewire 3 — six behaviours listened to it and never ran —
 * so this goes through Livewire's own hook, which does. The hook fires once
 * per morphed element and cannot be removed, so the work is coalesced to one
 * run per frame and parks itself the moment the component root detaches.
 */
export function afterMorph(root: HTMLElement, run: () => void): () => void {
  let frame = 0;
  let dead = false;

  const tick = () => {
    frame = 0;
    if (!dead && root.isConnected) run();
  };
  const schedule = () => {
    if (!frame) frame = requestAnimationFrame(tick);
  };

  const livewire = (window as window & { Livewire?: { hook?: (name: string, callback: () => void) => void } }).Livewire;
  if (typeof livewire?.hook === 'function') {
    livewire.hook('morph.updated', schedule);
  }

  return () => {
    dead = true;
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
  };
}
