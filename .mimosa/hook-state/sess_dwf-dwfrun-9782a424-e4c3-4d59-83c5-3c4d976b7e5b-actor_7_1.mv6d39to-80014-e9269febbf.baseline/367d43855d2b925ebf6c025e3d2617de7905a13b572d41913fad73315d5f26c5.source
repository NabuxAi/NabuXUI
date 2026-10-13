import '@nabuxai/ui-react/css';
import './app.css';
import { StrictMode, type ComponentType } from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';

/**
 * The Inertia entry. The root Blade view (inertia-root.blade.php) prints the
 * initial page JSON and the `#app` div — the same shape index.html mimics for
 * the standalone dev server — and loads this bundle from public/inertia/.
 */
void createInertiaApp({
  title: (title) => (title ? `${title} · NabuXUI · Inertia` : 'NabuXUI · Inertia'),
  // Pages are named after their file: inertia('Dashboard') → ./pages/Dashboard.tsx.
  resolve: (name) => {
    const pages = import.meta.glob<{ default: ComponentType }>('./pages/*.tsx', { eager: true });
    const page = pages[`./pages/${name}.tsx`];
    if (!page) throw new Error(`Unknown Inertia page: ${name}`);
    return page;
  },
  setup({ el, App, props }) {
    createRoot(el).render(
      <StrictMode>
        <App {...props} />
      </StrictMode>,
    );
  },
});
