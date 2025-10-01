import { CustomRoutes, memoryStore, Resource } from 'react-admin';
import { Layout } from "./Layout";
import { HydraAdmin } from '@api-platform/admin';
import { Route } from 'react-router';
import QuizPage from './component/QuizPage.tsx';
import HomePage from './component/HomePage.tsx';
import ResultPage from './component/ResultPage.tsx';
import CareerList from './component/admin/career/CareerList.tsx';
import CareerCreate from './component/admin/career/CareerCreate.tsx';
import CareerEdit from './component/admin/career/CareerEdit.tsx';
import QuestionList from './component/admin/question/QuestionList.tsx';
import { QuestionEdit } from './component/admin/question/QuestionEdit.tsx';
import { QuestionCreate } from './component/admin/question/QuestionCreate.tsx';

export const App = () => (
    <HydraAdmin
        layout={Layout}
        entrypoint={import.meta.env.VITE_ENTRYPOINT}
        disableTelemetry
        store={memoryStore()}
    >
        <CustomRoutes noLayout>
            <Route path="/" element={<HomePage />} />
            <Route path="/quiz" element={<QuizPage />} />
            <Route path="/result" element={<ResultPage />} />
        </CustomRoutes>
        <Resource name={'careers'} list={CareerList} create={CareerCreate} edit={CareerEdit} />
        <Resource name={'questions'} list={QuestionList} create={QuestionCreate} edit={QuestionEdit} />
        <Resource name="question_career_weights" />
        <Resource name={'assessments'} />
    </HydraAdmin>
);
