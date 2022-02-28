import React from 'react';


import { Route,BrowserRouter as Router, Routes,Link } from "react-router-dom";
import Home from "./components/Home";
import ListInvestiments from "./components/ListInvestiments";
import RegisterOwner from "./components/RegisterOwner";
import ViewInvestiment from "./components/ViewInvestiment";
import RegisterInvestiment from "./components/RegisterInvestiment";
import WithdrawalInvestiment from "./components/WithdrawalInvestiment";


const App = () => {

    return (
      <div className="App">
          <Router>
            <Routes>
                <Route path="/" element={<Home/>}/>
                <Route path="/listInvestiments"  element={<ListInvestiments/>}/>
                <Route path="/registerOwner"  element={<RegisterOwner/>}/>
                <Route path="/withdrawalInvestiment"  element={<WithdrawalInvestiment/>}/>
                <Route path="/registerInvestiment"  element={<RegisterInvestiment/>}/>
                <Route path="/viewInvestiment"  element={<ViewInvestiment/>}/>
            </Routes>  
          </Router>
        
      </div>
    );
  
}

export default App;