import 'bootstrap/dist/css/bootstrap.min.css'; 
import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import axios from 'axios';

const Article = () => {
  const router = useRouter();
  const { id } = router.query;
  const [article, setArticle] = useState({ title: '', content: '' });
  const [isEditing, setIsEditing] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('token');

    if (!token) {
      router.push('/login');
      return;
    }

    if (id) {
      axios.get(`http://localhost/api/article/${id}`, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
        .then(response => setArticle(response.data))
        .catch(error => console.error(error));
    }
  }, [id]);

  const handleUpdate = async () => {
    const token = localStorage.getItem('token');
    try {
      await axios.put(`http://localhost/api/article/${id}`, article, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      setIsEditing(false);
      router.push('/articles');
    } catch (error) {
      console.error(error);
    }
  };

  const handleDelete = async () => {
    const token = localStorage.getItem('token');
    try {
      await axios.delete(`http://localhost/api/article/${id}`, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      router.push('/articles');
    } catch (error) {
      console.error(error);
    }
  };

  return (
    <div className="w-50 mx-auto">
      {isEditing ? (
        <div className="container">
          <h1>Edit Article</h1>
          <div className="form-group">
            <label >Title</label>
            <input
              className="form-control"
              type="text"
              value={article.title}
              onChange={(e) => setArticle({ ...article, title: e.target.value })}
            />
          </div>
          <div className="form-group">
            <label>Content</label>
            <textarea
              className="form-control"
              value={article.content}
              onChange={(e) => setArticle({ ...article, content: e.target.value })}
            />
          </div>
          <button className="btn btn-primary mt-3" onClick={handleUpdate}>Save</button>
        </div>
      ) : (
        <div>
          <h1>{article.title}</h1>
          <p>{article.content}</p>
          <button className="btn btn-primary ml-2 mt-3" onClick={() => setIsEditing(true)}>Edit</button>
          <button className="btn btn-danger mt-3" onClick={handleDelete}>Delete</button>
        </div>
      )}
    </div>
  );
};

export default Article;
