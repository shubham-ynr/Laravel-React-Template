import './bootstrap';
import '../css/app.css';
import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'
import { ThemeProvider } from './components/ui/theme-provider';
import { Toaster } from 'sonner';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
    return pages[`./Pages/${name}.jsx`]
  },
  setup({ el, App, props }) {
    createRoot(el).render(
      <ThemeProvider defaultTheme="system" storageKey="theme">
        <App {...props} />
        <Toaster position="bottom-center" />
      </ThemeProvider>
    )
  },
  progress: {
    color: '#4B5563',
  },
})