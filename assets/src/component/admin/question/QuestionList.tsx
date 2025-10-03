import {
    List,
    Datagrid,
    TextField,
    ReferenceManyField,
    SingleFieldList,
    useRecordContext, BooleanField,
} from 'react-admin';
import { Chip } from '@mui/material';

const CareerWeightChip = () => {
    const record = useRecordContext<any>();
    if (!record) return null;

    return (
        <Chip
            label={`${record.career?.name} – ${record?.yesWeight ?? 0} - ${record?.noWeight ?? 0}`}
            size="small"
            sx={{m: 0.5}}
        />
    );
};

export default function QuestionList() {
    return (
        <List>
            <Datagrid rowClick="edit" bulkActionButtons={false}>
                <TextField source="message"/>
                <ReferenceManyField
                    label="Career Weights"
                    reference="question_career_weights"
                    target="question"
                >
                    <SingleFieldList>
                        <CareerWeightChip/>
                    </SingleFieldList>
                </ReferenceManyField>
                <BooleanField
                    source={'active'}
                    valueLabelTrue={'Active'}
                    valueLabelFalse={'Not Active'}
                />
            </Datagrid>
        </List>
    );
};
