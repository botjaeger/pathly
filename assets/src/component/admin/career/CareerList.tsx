import { FieldGuesser, ListGuesser } from '@api-platform/admin';

export default function CareerList() {
    return (
        <ListGuesser sort={{ field: 'id', order: 'ASC' }}>
            <FieldGuesser source={'name'} label={'Name'} />
            <FieldGuesser source={'description'} label={'Description'} />
        </ListGuesser>
    );
}
