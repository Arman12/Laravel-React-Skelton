import React, { Suspense } from 'react'; 
import './App.css';
import { Routes, Route, BrowserRouter as Router, } from "react-router-dom";
import TopBarProgress from "./components/common/top-bar-config";


import Layout from './components/static/layout/Layout';
import Home from './components/static/Home';
import Thanks from './components/common/Thanks';

function App() {
  return (
          <Suspense fallback={<TopBarProgress />}>
            <Routes>
              <Route path="/" element={<Layout />}>
                <Route index element={<Home />} />
                <Route path="/thanks" element={<Thanks />} />
              </Route>
            </Routes>
          </Suspense>
  );
}

export default App;
