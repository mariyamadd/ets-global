import 'bootstrap/dist/css/bootstrap.min.css'; 
import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import axios from 'axios';

const User = () => {
  const router = useRouter();
  const { id } = router.query;
  const [user, setUser] = useState({ name: '', email: '' , password: ''});
  const [isEditing, setIsEditing] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('token');

    if (!token) {
      router.push('/login');
      return;
    }

    if (id) {
      axios.get(`http://localhost/api/user/${id}`, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
        .then(response => setUser(response.data))
        .catch(error => console.error(error));
    }
  }, [id]);

  const handleUpdate = async () => {
    const token = localStorage.getItem('token');
    try {
      await axios.put(`http://localhost/api/user/${id}`, user, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      setIsEditing(false);
      router.push('/users');
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
              onChange={(e) => setArticle({ ...user, name: e.target.value })}
            />
          </div>
          <div className="form-group">
            <label>email</label>
            <input
              className="form-control"
              value={user.email}
              onChange={(e) => setArticle({ ...user, email: e.target.value })}
            />
          </div>
          <button className="btn btn-primary mt-3" onClick={handleUpdate}>Save</button>
        </div>
      ) : (
        <div>
          <h1>{article.name}</h1>
          <p>{article.email}</p>
          <button className="btn btn-primary ml-2 mt-3" onClick={() => setIsEditing(true)}>Edit</button>
        </div>
      )}
    </div>
  );
};

export default User;
