import {
    ArrayInput, BooleanInput,
    NumberInput,
    ReferenceInput,
    SelectInput,
    SimpleForm,
    SimpleFormIterator,
    TextInput,
} from 'react-admin';
import { Stack } from '@mui/material';

export const QuestionForm = () => {
    return (
        <SimpleForm sx={{maxWidth: 500}}>
            <BooleanInput source={'active'} />
            <TextInput source="message" fullWidth multiline/>
            <ArrayInput source="questionCareerWeights">
                <SimpleFormIterator>
                    <Stack direction="row" gap={2}>
                        <ReferenceInput
                            source="career"
                            reference="careers"
                            label="Career"
                        >
                            <SelectInput optionText="name"/>
                        </ReferenceInput>
                        <NumberInput source="yesWeight" label="Yes Weight"/>
                        <NumberInput source="noWeight" label="No Weight"/>
                    </Stack>
                </SimpleFormIterator>
            </ArrayInput>
        </SimpleForm>
    );
};
