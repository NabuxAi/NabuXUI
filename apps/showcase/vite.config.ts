import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// The showcase consumes the built packages, exactly as an app would.
export default defineConfig({
  plugins: [react()],
  base: './',
  server: { port: 5199, strictPort: true },
});
