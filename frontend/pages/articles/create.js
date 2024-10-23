import 'bootstrap/dist/css/bootstrap.min.css'; 
import { useState } from 'react';
import { useRouter } from 'next/router';
import axios from 'axios';

const CreateArticle = () => {
  const [title, setTitle] = useState('');
  const [content, setContent] = useState('');
  const author = localStorage.getItem('userId');
  const router = useRouter();

  const handleSubmit = async (e) => {
    e.preventDefault();
    const token = localStorage.getItem('token');

    if (!token) {
      router.push('/login');
      return;
    } 
    try {
      await axios.post('http://localhost/api/article', { title, content, author }, {
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
    <div className="container w-50 mx-auto">
      <h1>Create New Article</h1>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Title</label>
          <input
            className="form-control" 
            type="text"
            value={title}
            onChange={(e) => setTitle(e.target.value)}
            required
          />
        </div>
        <div className="form-group">
          <label>Content</label>
          <textarea
            className="form-control" 
            value={content}
            onChange={(e) => setContent(e.target.value)}
            required
          />
        </div>
        <button className="btn btn-primary mt-3" type="submit">Create</button>
      </form>
    </div>
  );
};

export default CreateArticle;
