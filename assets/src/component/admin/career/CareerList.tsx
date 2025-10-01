import {
    List,
    TopToolbar,
    CreateButton, DataTable,
} from 'react-admin';

export default function CareerList() {
    return (
        <List
            sort={{ field: 'id', order: 'ASC' }}
            actions={
                <TopToolbar>
                    <CreateButton />
                </TopToolbar>
            }
        >
            <DataTable
                rowClick="edit"
                bulkActionButtons={false}
            >
                <DataTable.Col source="name" label="Name" />
                <DataTable.Col source="description" label="Description" />
            </DataTable>
        </List>
    );
}
