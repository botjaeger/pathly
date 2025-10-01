import { SimpleForm, TextInput } from 'react-admin';

export default function CareerForm() {
    return (
        <SimpleForm sx={{ maxWidth: 500 }}>
            <TextInput source="name" />
            <TextInput source="description" />
        </SimpleForm>
    );
}
