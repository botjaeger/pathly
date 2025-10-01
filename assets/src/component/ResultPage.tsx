import { useEffect, useRef, useState } from 'react';
import { useLocation, useNavigate } from "react-router-dom";
import { useDataProvider, useResetStore } from 'react-admin';
import {
    Container,
    Typography,
    Stack,
    Button,
    Box,
    CircularProgress,
    useTheme,
} from "@mui/material";

type TopCareer = {
    name: string;
    description?: string;
    percentage: number;
};

type AssessmentResult = {
    totals: Record<string, number>;
    percentages: Record<string, number>;
    top: TopCareer[];
};

export default function ResultPage() {
    const navigate = useNavigate();
    const location = useLocation();
    const dataProvider = useDataProvider();
    const theme = useTheme();
    const reset = useResetStore();

    const answers = location.state?.answers || {};
    const [result, setResult] = useState<AssessmentResult | null>(null);
    const [loading, setLoading] = useState(true);
    const submittedRef = useRef(false);

    useEffect(() => {
        if (submittedRef.current) return;
        submittedRef.current = true;

        const submit = async () => {
            try {
                const res = await dataProvider.create("assessments", { data: answers });
                setResult(res.data as AssessmentResult);
            } finally {
                setLoading(false);
            }
        };

        void submit();
    }, [answers]);

    useEffect(() => {
        document.title = "Results - Multimedia Career Finder";
    }, []);

    if (loading) {
        return (
            <Box sx={{ display: "flex", justifyContent: "center", mt: 10 }}>
                <CircularProgress />
            </Box>
        );
    }

    if (!result) return <p>No result available</p>;

    const colors = [
        theme.palette.primary.main,
        theme.palette.success.light,
        theme.palette.grey[900],
    ];
    const topCareers = result.top;

    return (
        <Container sx={{ textAlign: "center", mt: 6, maxWidth: 600 }}>
            <Typography variant="h5" fontWeight="bold" sx={{ mb: 4 }}>
                Result
            </Typography>

            <Stack spacing={3}>
                {topCareers.map((career, i) => (
                    <Box
                        key={career.name}
                        sx={{
                            position: "relative",
                            width: "100%",
                            height: 40,
                            borderRadius: 50,
                            bgcolor: theme.palette.action.disabledBackground,
                            overflow: "hidden",
                        }}
                    >
                        {/* Colored fill */}
                        <Box
                            sx={{
                                position: "absolute",
                                top: 0,
                                left: 0,
                                height: "100%",
                                width: `${career.percentage}%`,
                                bgcolor: colors[i % colors.length],
                                borderRadius: 50,
                                transition: "width 0.5s ease",
                            }}
                        />

                        {/* Text on top */}
                        <Box
                            sx={{
                                position: "relative",
                                zIndex: 1,
                                display: "flex",
                                justifyContent: "space-between",
                                alignItems: "center",
                                px: 2,
                                height: "100%",
                                fontWeight: "bold",
                                color: theme.palette.getContrastText(colors[i % colors.length]),
                            }}
                        >
                            <Typography>{career.name}</Typography>
                            <Typography>{career.percentage}%</Typography>
                        </Box>
                    </Box>
                ))}
            </Stack>

            {topCareers[0] && (
                <Box
                    sx={{
                        mt: 6,
                        textAlign: "left",
                        bgcolor: theme.palette.background.paper,
                        borderRadius: 2,
                        p: 3,
                    }}
                >
                    <Typography variant="subtitle1" fontWeight="bold" sx={{ mb: 1 }}>
                        {topCareers[0].name}
                    </Typography>
                    <Typography variant="body1">
                        {topCareers[0].description || "Explore this career path further!"}
                    </Typography>
                </Box>
            )}

            <Button
                onClick={() => {
                    reset();
                    navigate("/");
                }}
                variant="contained"
                color="success"
                size="large"
                sx={{ borderRadius: 50, mt: 4 }}
            >
                Start again →
            </Button>
        </Container>
    );
}
