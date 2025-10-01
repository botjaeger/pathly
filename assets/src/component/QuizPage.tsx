import { useEffect, useState } from 'react';
import {
    Container,
    Typography,
    Button,
    Stack,
    RadioGroup,
    FormControlLabel,
    Radio,
    LinearProgress, Box, CircularProgress,
} from '@mui/material';
import { useNavigate } from "react-router-dom";
import { ListBase, WithListContext } from "react-admin";

type Question = {
    id: number;
    message: string;
};

const PER_PAGE = 100;

function shuffle<T>(array: T[]): T[] {
    return array
        .map((value) => ({ value, sort: Math.random() }))
        .sort((a, b) => a.sort - b.sort)
        .map(({ value }) => value);
}

export default function QuizPage() {
    const navigate = useNavigate();
    const [answers, setAnswers] = useState<Record<number, string>>({});
    const [currentIndex, setCurrentIndex] = useState(0);
    const [shuffled, setShuffled] = useState<Question[]>([]);

    const handleAnswer = (qid: number, value: string) => {
        setAnswers((prev) => ({ ...prev, [qid]: value }));
    };

    useEffect(() => {
        document.title = "Quiz - Multimedia Career Finder";
        setAnswers({});
        setCurrentIndex(0);
        setShuffled([]);
    }, []);

    return (
        <ListBase resource="questions" perPage={PER_PAGE} disableAuthentication filter={{ active: true }}>
            <WithListContext
                render={({ data, total, hasNextPage, isLoading, page, setPage }) => {
                    if (isLoading || !data) {
                        return (
                            <Box sx={{ display: "flex", justifyContent: "center", mt: 10 }}>
                                <CircularProgress />
                            </Box>
                        );
                    }

                    if (shuffled.length === 0) {
                        setShuffled(shuffle(Object.values(data) as Question[]));
                    }

                    const totalQuestions = shuffled.length || total || 1;
                    const progress = ((currentIndex + 1) / totalQuestions) * 100;

                    const offset = (page - 1) * PER_PAGE;
                    const q = shuffled[currentIndex - offset];

                    if (!q) {
                        return (
                            <Box sx={{ display: "flex", justifyContent: "center", mt: 10 }}>
                                <CircularProgress />
                            </Box>
                        );
                    }

                    const handleNext = () => {
                        const nextIndex = currentIndex + 1;
                        if (nextIndex < totalQuestions) {
                            setCurrentIndex(nextIndex);
                            if (nextIndex % PER_PAGE === 0 && hasNextPage) {
                                setPage(page + 1);
                            }
                        } else {
                            navigate("/result", { state: { answers } });
                        }
                    };

                    const handleBack = () => {
                        if (currentIndex > 0) {
                            const prevIndex = currentIndex - 1;
                            setCurrentIndex(prevIndex);
                            // If we cross a page boundary backwards
                            if (prevIndex % PER_PAGE === PER_PAGE - 1 && page > 1) {
                                setPage(page - 1);
                            }
                        }
                    };

                    return (
                        <Container sx={{ textAlign: "center", mt: 10 }}>
                            <LinearProgress
                                variant="determinate"
                                value={progress}
                                sx={{ mb: 3 }}
                            />
                            <Typography
                                variant="h6"
                                fontWeight="bold"
                                color="primary"
                                sx={{ mb: 4 }}
                            >
                                {q.message}
                            </Typography>

                            <RadioGroup
                                value={answers[q.id] || ""}
                                onChange={(e) => handleAnswer(q.id, e.target.value)}
                            >
                                <Stack direction="row" spacing={2} justifyContent="center">
                                    <FormControlLabel value="yes" control={<Radio />} label="Yes" />
                                    <FormControlLabel value="maybe" control={<Radio />} label="Maybe" />
                                    <FormControlLabel value="no" control={<Radio />} label="No" />
                                </Stack>
                            </RadioGroup>

                            <Stack direction="row" justifyContent="space-between" sx={{ mt: 4 }}>
                                <Button
                                    variant="outlined"
                                    disabled={currentIndex === 0}
                                    onClick={handleBack}
                                >
                                    ← Back
                                </Button>
                                <Button
                                    variant="contained"
                                    color="primary"
                                    disabled={!answers[q.id]}
                                    onClick={handleNext}
                                >
                                    {currentIndex + 1 < totalQuestions ? "Next →" : "Submit →"}
                                </Button>
                            </Stack>
                        </Container>
                    );
                }}
            />
        </ListBase>
    );
}
