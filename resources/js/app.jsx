import React from 'react';
import ReactDOM from 'react-dom/client';
import PetsCard from './components/PetsCard';
import Dashboard from './components/Dashboard';
import '../css/app.css';

const dashboard = document.getElementById('react-dashboard');
if (dashboard) ReactDOM.createRoot(dashboard).render(<Dashboard />);

const petsCard = document.getElementById('react-pets-card');
if (petsCard) ReactDOM.createRoot(petsCard).render(<PetsCard />);