import './bootstrap';

/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

// ✅ Updated FullCalendar CSS imports for v6
import '@fullcalendar/core/index.css';
import '@fullcalendar/daygrid/index.css';
