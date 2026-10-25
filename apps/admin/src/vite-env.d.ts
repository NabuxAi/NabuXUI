/// <reference types="vite/client" />

// The css subpath of the workspace packages points at a stylesheet, which has no
// types; side-effect imports need an ambient module under TS7 (TS2882).
declare module '@nabuxai/ui-react/css';
