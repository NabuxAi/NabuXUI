import '@nabuxai/ui-core/css';
import './app.css';
import { createApp } from 'vue';
import App from './App.vue';

// The theme script itself lives inline in index.html <head> (the pattern from
// docs/FRAMEWORKS.md) so the first frame is already in the right theme; here we
// only mount. The core css lands first (the wrapper package's css is the same
// file) and app.css carries only the panel's own layout. Component pages — the
// gallery — import @nabuxai/ui-vue; the rest write the same nx-* markup.
createApp(App).mount('#root');
