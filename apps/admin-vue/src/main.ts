import '@nabuxai/ui-core/css';
import './app.css';
import { createApp } from 'vue';
import App from './App.vue';

// The theme script itself lives inline in index.html <head> (the pattern from
// docs/FRAMEWORKS.md) so the first frame is already in the right theme; here we
// only mount. There is no Vue wrapper package — the app writes the same nx-*
// markup the React/Blade components render and calls the core behaviours.
createApp(App).mount('#root');
