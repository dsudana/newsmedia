/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            colors: {
                // Exact tokens sampled from the RetNews screenshot
                rn: {
                    red: "#ED1C29", // primary brand red / category tags / accent underline
                    "red-dark": "#C81824",
                    ink: "#171717", // topbar + headline black
                    body: "#4B4B4B", // paragraph grey
                    muted: "#7A7A7A", // byline grey / meta text
                    line: "#ECECEC", // hairline dividers
                    panel: "#F5F5F6", // grey strip under header
                    fb: "#3B5998",
                    tw: "#1DA1F2",
                },
            },
            fontFamily: {
                sans: ["Inter", "-apple-system", "BlinkMacSystemFont", "ui-sans-serif", "system-ui", "sans-serif"],
            },
            fontSize: {
                // tuned to match the screenshot's tight headline sizes
                "card-title": [
                    "15px",
                    { lineHeight: "1.35", fontWeight: "700" },
                ],
                "hero-title": [
                    "28px",
                    { lineHeight: "1.25", fontWeight: "700" },
                ],
            },
            maxWidth: {
                wrap: "1200px",
            },
            animation: {
                marquee: 'marquee 60s linear infinite',
                'marquee-slow': 'marquee 90s linear infinite',
            },
            keyframes: {
                marquee: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-100%)' },
                },
            },
        },
    },
    plugins: [],
};
