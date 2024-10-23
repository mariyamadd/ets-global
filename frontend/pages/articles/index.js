import 'bootstrap/dist/css/bootstrap.min.css'; 
import { useEffect, useState } from 'react';
import Link from 'next/link';
import axios from 'axios';
import { useRouter } from 'next/router';

const ArticleList = () => {
  const [articles, setArticles] = useState([]);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem('token');

    if (!token) {
      router.push('/login');
      return;
    }

    axios.get('http://localhost/api/article', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
      .then(response => setArticles(response.data))
      .catch(error => console.error(error));
  }, []);
  const handleClick = () => {
    router.push('/articles/create'); 
  };
  return (
    <div className="container w-50">
     <button className="btn btn-primary my-3" type="button" onClick={handleClick}>Nouvel Article</button>
      <div className='w-50 row mx-auto'>
        <h2>Mes articles</h2>
        <ul className="list-group">
          {articles.map(article => (
            <li className="list-group-item" key={article.id}>
              <Link href={`/articles/${article.id}`}>
                {article.title}
              </Link>
              <span className='ml-2'>{article.author}</span>
              <span className='ml-2'>{article.publishedDate}</span>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default ArticleList;
