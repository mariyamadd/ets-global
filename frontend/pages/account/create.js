import 'bootstrap/dist/css/bootstrap.min.css'; 
import { useState } from 'react';
import { useRouter } from 'next/router';
import axios from 'axios';

const CreateUser = () => {
  const [username, setName] = useState('');
  const [email, setEmail] = useState('');
  const router = useRouter();

  const handleSubmit = async (e) => {
    e.preventDefault();
    const token = localStorage.getItem('token');
    try {
      await axios.post('http://localhost/api/user', { username, email, password }, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      router.push('/users');
    } catch (error) {
      console.error(error);
    }
  };

  return (
    <div className="container w-50 mx-auto" >
      <h1>Create New User</h1>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Name</label>
          <input
            className="form-control"
            type="text"
            value={username}
            onChange={(e) => setName(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label>email</label>
          <input
            className="form-control"
            type="text"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label>password</label>
          <input
            className="form-control"
            type="text"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>
        <button className="btn btn-primary mt-3" type="submit">Create</button>
      </form>
    </div>
  );
};

export default CreateUser;
