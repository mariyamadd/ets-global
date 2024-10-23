import 'bootstrap/dist/css/bootstrap.min.css'; 
import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import Link from 'next/link';
import axios from 'axios';
import LogoutButton from '../../components/LogoutButton';

const AccountPage = () => {
  const router = useRouter();
  const { id } = router.query;
  const [user, setUser] = useState({ name: '', email: '' , password: ''});
  const [error, setError] = useState('');
  const [isEditing, setIsEditing] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('token');

    if (!token) {
      router.push('/login');
      return;
    }

    const verifyToken = async () => {
      try {
        const response = await axios.get('http://localhost/api/verify', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setUser(response.data); 
      } catch (error) {
        setError('Invalid token');
        localStorage.removeItem('token');
        router.push('/login');
      }
    };

    verifyToken();
  }, []); 

  if (error) return <p>{error}</p>;

  if (!user) {
    return (
      <div className="container">
        <p>Loading...</p>
      </div>
    );
  }
  
  const handleUpdate = async () => {
    const token = localStorage.getItem('token');
    try {
      await axios.put(`http://localhost/api/user/${id}`, user, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      setIsEditing(false);
      router.push('/account');
    } catch (error) {
      console.error(error);
    }
  };

 
  return (
    <div className="w-50 mx-auto">
      {isEditing ? (
        <div className="container">
          <h1>Edit User</h1>
          <div className="form-group">
            <label >Name</label>
            <input
              className="form-control"
              type="text"
              value={user.name}
              onChange={(e) => setUser({ ...user, name: e.target.value })}
            />
          </div>
          <div className="form-group">
            <label>email</label>
            <input
              className="form-control"
              value={user.email}
              onChange={(e) => setUser({ ...user, email: e.target.value })}
            />
          </div>
          <button className="btn btn-primary mt-3" onClick={handleUpdate}>Save</button>
        </div>
      ) : (
        <div>
          <div className="container w-50 mx-auto">
            <Link href="/articles">
              Mes Articles
            </Link>
            <div className="card">
            <LogoutButton />
              <div className="card-body">
                <h4>Nom: {user.name}</h4> 
                <h4>Email: {user.email}</h4>
              </div>
            </div>
          </div>
          <button className="btn btn-primary ml-2 mt-3" onClick={() => setIsEditing(true)}>Edit</button>
        </div>
      )}
    </div>
  );
};

export default AccountPage;
