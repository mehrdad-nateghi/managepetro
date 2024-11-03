// src/components/OrderForm.jsx
import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from '../utils/axios';

const OrderForm = () => {
    const navigate = useNavigate();
    const [clients, setClients] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState('');
    const [formData, setFormData] = useState({
        clientId: '',
        orderType: '',
        description: '',
        quantity: '',
        deliveryDate: '',
        status: 'PENDING'
    });
    const [isSubmitting, setIsSubmitting] = useState(false);

    const getToken = () => {
        const token = localStorage.getItem('token');
        console.log('Current token:', token); // Debug log
        return token;
    };

    const fetchClients = async () => {
        setIsLoading(true);
        setError('');

        const token = getToken();
        if (!token) {
            console.log('No token found, redirecting to login');
            navigate('/login');
            return;
        }

        try {
            // Explicitly set the Authorization header
            const response = await axios.get('/api/clients', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            console.log('Clients response:', response);
            setClients(response.data);
        } catch (error) {
            console.error('Error fetching clients:', error.response || error);
            if (error.response?.data?.message === 'No API token provided' || error.response?.status === 401) {
                localStorage.removeItem('token');
                navigate('/login');
            } else {
                setError('Failed to load clients. Please try again.');
            }
        } finally {
            setIsLoading(false);
        }
    };

    // Initialize auth check and client fetch
    useEffect(() => {
        const token = getToken();
        if (!token) {
            navigate('/login');
        } else {
            fetchClients();
        }
    }, [navigate]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setIsSubmitting(true);

        const token = getToken();
        if (!token) {
            navigate('/login');
            return;
        }

        try {
            const response = await axios.post('/api/orders', formData, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            console.log('Order created:', response.data);
            navigate('/orders');
        } catch (error) {
            console.error('Error creating order:', error);
            setError(error.response?.data?.message || 'Failed to create order');
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    if (isLoading) {
        return <div>Loading...</div>;
    }

    return (
        <div className="max-w-2xl mx-auto p-4">
            {error && (
                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {error}
                </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                    <label className="block mb-2">Client</label>
                    <select
                        name="clientId"
                        value={formData.clientId}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                    >
                        <option value="">Select Client</option>
                        {clients.map(client => (
                            <option key={client.id} value={client.id}>
                                {client.name}
                            </option>
                        ))}
                    </select>
                </div>

                <div>
                    <label className="block mb-2">Order Type</label>
                    <input
                        type="text"
                        name="orderType"
                        value={formData.orderType}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                    />
                </div>

                <div>
                    <label className="block mb-2">Description</label>
                    <textarea
                        name="description"
                        value={formData.description}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                    />
                </div>

                <div>
                    <label className="block mb-2">Quantity</label>
                    <input
                        type="number"
                        name="quantity"
                        value={formData.quantity}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                    />
                </div>

                <div>
                    <label className="block mb-2">Delivery Date</label>
                    <input
                        type="date"
                        name="deliveryDate"
                        value={formData.deliveryDate}
                        onChange={handleChange}
                        className="w-full p-2 border rounded"
                    />
                </div>

                <button
                    type="submit"
                    disabled={isSubmitting}
                    className="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 disabled:bg-blue-300"
                >
                    {isSubmitting ? 'Creating Order...' : 'Create Order'}
                </button>
            </form>
        </div>
    );
};

export default OrderForm;