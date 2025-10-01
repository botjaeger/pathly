import React, { useEffect, useMemo, useState } from 'react';
import {
    Container,
    Typography,
    Button,
    Stack,
    RadioGroup,
    FormControlLabel,
    Radio,
    LinearProgress,
    Box,
    CircularProgress,
} from '@mui/material';
import { useNavigate, useLocation } from "react-router-dom";
import { ListBase, useListContext } from "react-admin";

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

function QuizContent({
    answers,
    setAnswers,
    currentIndex,
    setCurrentIndex,
}: {
    answers: Record<number, string>;
    setAnswers: React.Dispatch<React.SetStateAction<Record<number, string>>>;
    currentIndex: number;
    setCurrentIndex: React.Dispatch<React.SetStateAction<number>>;
}) {
    const navigate = useNavigate();
    const { data, total, isLoading } = useListContext();

    // Shuffle questions once
    const shuffled: Question[] = useMemo(() => {
        if (!data) return [];
        return shuffle(Object.values(data) as Question[]);
    }, [data]);

    const totalQuestions = shuffled.length || total || 1;
    const q = shuffled[currentIndex];

    if (isLoading || !q) {
        return (
            <Box sx={{ display: "flex", justifyContent: "center", mt: 10 }}>
                <CircularProgress />
            </Box>
        );
    }

    const progress = ((currentIndex + 1) / totalQuestions) * 100;
    const isLastQuestion = currentIndex === totalQuestions - 1;
    const isAnswered = !!answers[q.id];

    const handleAnswer = (qid: number, value: string) => {
        setAnswers((prev) => ({ ...prev, [qid]: value }));

        if (!isLastQuestion) {
            // auto-advance if not last question
            setTimeout(() => {
                setCurrentIndex((prev) => prev + 1);
            }, 300);
        }
    };

    const handleBack = () => {
        if (currentIndex > 0) {
            setCurrentIndex((prev) => prev - 1);
        }
    };

    const handleNext = () => {
        const nextIndex = currentIndex + 1;
        if (nextIndex < totalQuestions) {
            setCurrentIndex(nextIndex);
        }
    };

    const handleSubmit = () => {
        navigate("/result", { state: { answers } });
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

                {!isLastQuestion ? (
                    <Button
                        variant="contained"
                        color="primary"
                        disabled={!isAnswered}
                        onClick={handleNext}
                    >
                        Next →
                    </Button>
                ) : (
                    <Button
                        variant="contained"
                        color="success"
                        disabled={!isAnswered}
                        onClick={handleSubmit}
                    >
                        Submit →
                    </Button>
                )}
            </Stack>
        </Container>
    );
}

export default function QuizPage() {
    const location = useLocation();
    const [answers, setAnswers] = useState<Record<number, string>>({});
    const [currentIndex, setCurrentIndex] = useState(0);

    useEffect(() => {
        document.title = "Quiz - Multimedia Career Finder";
        setAnswers({});
        setCurrentIndex(0);
    }, [location.key]);

    return (
        <ListBase
            resource="questions"
            perPage={PER_PAGE}
            disableAuthentication
            filterDefaultValues={{ active: true }}
        >
            <QuizContent
                answers={answers}
                setAnswers={setAnswers}
                currentIndex={currentIndex}
                setCurrentIndex={setCurrentIndex}
            />
        </ListBase>
    );
}
