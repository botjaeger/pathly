import {
    Edit,
} from 'react-admin';
import { QuestionForm } from './QuestionForm.tsx';

export const QuestionEdit = () => (
    <Edit
        mutationMode={'optimistic'}
        transform={(data) => {
            const { id, ...rest } = data;
            return rest;
        }}
    >
        <QuestionForm />
    </Edit>
);
