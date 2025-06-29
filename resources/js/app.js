import './bootstrap';
import 'flowbite';
import 'flowbite-datepicker';

import Alpine from 'alpinejs';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

window.myFunctionName = function(parameter){
  let calendarEl = document.getElementById('calendar');
  let calendar = new Calendar(calendarEl, {
    dateClick: function() {
    //   alert('a day has been clicked!');
      alert("The view's title is " + view.description);
    },
  plugins: [  dayGridPlugin,
              timeGridPlugin,
              listPlugin,
              interactionPlugin ],

  initialView: 'dayGridMonth',
  selectable: true,
  events: '/events',
  eventTextColor: 'black',
  editable: false,

  headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,listWeek'
  }


  });
  // calendar.batchRendering(function() {
  //   calendar.changeView('dayGridMonth');
  //   calendar.addEvent({ title: 'new event', 
  //                       start: '2025-05-01' });
  // });

  calendar.render();
}

window.myDarkMode = function(paramete){
  var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
  var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

  // Change the icons inside the button based on previous settings
  if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      themeToggleLightIcon.classList.remove('hidden');
  } else {
      themeToggleDarkIcon.classList.remove('hidden');
  }

  var themeToggleBtn = document.getElementById('theme-toggle');

  themeToggleBtn.addEventListener('click', function() {

      // toggle icons inside button
      themeToggleDarkIcon.classList.toggle('hidden');
      themeToggleLightIcon.classList.toggle('hidden');

      // if set via local storage previously
      if (localStorage.getItem('color-theme')) {
          if (localStorage.getItem('color-theme') === 'light') {
              document.documentElement.classList.add('dark');
              localStorage.setItem('color-theme', 'dark');
          } else {
              document.documentElement.classList.remove('dark');
              localStorage.setItem('color-theme', 'light');
          }

      // if NOT set via local storage previously
      } else {
          if (document.documentElement.classList.contains('dark')) {
              document.documentElement.classList.remove('dark');
              localStorage.setItem('color-theme', 'light');
          } else {
              document.documentElement.classList.add('dark');
              localStorage.setItem('color-theme', 'dark');
          }
      }
      
  });
}

window.Alpine = Alpine;

Alpine.start();
