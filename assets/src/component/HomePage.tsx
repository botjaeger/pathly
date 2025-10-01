import { Container, Typography, Button } from "@mui/material";
import { useNavigate } from "react-router-dom";
import { useEffect } from 'react';
import { useResetStore } from 'react-admin';

export default function HomePage() {
    const navigate = useNavigate();
    const reset = useResetStore();

    useEffect(() => {
        document.title = "Multimedia Career Finder";
    }, []);

    return (
        <Container sx={{ textAlign: "center", mt: 10 }}>
            <Typography variant="h4" fontWeight="bold" color="primary">
                Career Guidance System for Multimedia Careers
            </Typography>
            <Typography variant="body1" sx={{ mt: 2, mb: 4 }}>
                Find out which Multimedia Arts field matches your strengths and interests by
                answering questions about your skills and preferences.
            </Typography>
            <Button
                variant="contained"
                color="success"
                size="large"
                sx={{ borderRadius: 50, px: 4 }}
                onClick={() => {
                    reset();
                    navigate('/quiz');
                }}
            >
                Start →
            </Button>
        </Container>
    );
}
