import '@nabuxai/ui-react/css';
import './admin.css';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { App } from './App';

// The theme script itself lives inline in index.html <head> (the pattern from
// docs/FRAMEWORKS.md and apps/showcase) so the first frame is already in the
// right theme; here we only mount. The provider with locale="fa" wraps the App.
createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <App />
  </StrictMode>,
);
