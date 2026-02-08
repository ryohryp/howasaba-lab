import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { Layout } from './layouts/Layout';
import { Home } from './pages/Home';
import { Tools } from './pages/Tools';
import { About } from './pages/About';
import { HeroList } from './pages/hero/HeroList';
import { HeroDetail } from './pages/hero/HeroDetail';
import { GuideList } from './pages/guide/GuideList';
import { GuideDetail } from './pages/guide/GuideDetail';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Layout />}>
          <Route index element={<Home />} />
          <Route path="tools" element={<Tools />} />
          <Route path="about" element={<About />} />

          <Route path="hero">
            <Route index element={<HeroList />} />
            <Route path=":id" element={<HeroDetail />} />
          </Route>

          <Route path="guide">
            <Route index element={<GuideList />} />
            <Route path=":id" element={<GuideDetail />} />
          </Route>
        </Route>
      </Routes>
    </Router>
  );
}

export default App;
