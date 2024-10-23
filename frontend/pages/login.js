import 'bootstrap/dist/css/bootstrap.min.css'; 
import { useState } from 'react';
import axios from 'axios';
import { useRouter } from 'next/router';

const LoginPage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const router = useRouter();

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost/api/login', { email, password });
      const { token } = response.data;

      localStorage.setItem('token', token);

      router.push('/account');
    } catch (error) {
      setError('Invalid credentials');
    }
  };

  return (
    <div className="container w-50 mx-auto">
      <form onSubmit={handleLogin}>
        <h1>Login</h1>
        {error && <p className="error">{error}</p>}
        <div className="form-group">
          <label>Email</label>
          <input
            className="form-control"
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label>Password</label>
          <input
            className="form-control"
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>
        <button className="btn btn-primary my-2 mt-3" type="submit">Login</button>
      </form>
    </div>
  );
};

export default LoginPage;
