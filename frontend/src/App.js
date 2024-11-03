// src/App.jsx
import {BrowserRouter as Router, Routes, Route, Navigate} from 'react-router-dom';
import {AuthProvider} from './context/AuthContext';
import Login from './components/Login';
import OrderForm from './components/OrderForm';

const PrivateRoute = ({children}) => {
    const token = localStorage.getItem('token');
    return token ? children : <Navigate to="/login"/>;
};

const App = () => {
    return (
        <AuthProvider>
            <Router>
                <Routes>
                    <Route path="/login" element={<Login/>}/>
                    <Route path="/dashboard/orders/create" element={<OrderForm/>}/>
                    <Route
                        path="/dashboard"
                        element={
                            <PrivateRoute>
                                <div>Dashboard Content</div>
                            </PrivateRoute>
                        }
                    />
                    <Route path="/" element={<Navigate to="/dashboard"/>}/>
                </Routes>
            </Router>
        </AuthProvider>
    );
};

export default App;