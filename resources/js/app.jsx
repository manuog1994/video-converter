import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';
import { ConvertApp } from './ConvertApp';

import '../css/app.css'; 

ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
      <ConvertApp />
    </React.StrictMode>,
)
  