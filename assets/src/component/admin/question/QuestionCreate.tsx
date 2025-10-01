import { Create } from 'react-admin';
import { QuestionForm } from './QuestionForm.tsx';

export const QuestionCreate = () => {
    return (
        <Create>
            <QuestionForm />
        </Create>
    );
}
