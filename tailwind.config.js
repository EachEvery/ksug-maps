module.exports = {
    theme: {
        fontFamily: {
            display: ["bureau-grot-compressed", "arial black", "serif"],
            sans: ["signo", "sans-serif"],
            mono: ["letter-gothic-std", "monospace"]
        },
        fontSize: {
            "2xs": ".675rem",
            xs: ".875rem",
            sm: "1rem",
            base: "1.125rem",
            md: "1.25rem",
            lg: "1.5rem",
            xl: "1.875rem",
            "2xl": "2rem",
            "3xl": "2.5rem",
            "4xl": "2.8125rem",
            "5xl": "3.125rem"
        },
        extend: {
            colors: {
                current: "currentColor",
                orange: "#F0A38C",
                green: "#C6D6BD",
                blue: "#C9D9E0",
                purple: "#C6C1CE",
                tan: {
                    100: "#F0EBE6",
                    200: "#D8C6BF"
                }
            },
            spacing: {
                "72": "17rem",
                "84": "20.125rem",
                "96": "24rem",
                "48vh": "48vh",
                "55vh": "55vh"
            }
        }
    },
    variants: {},
    plugins: [
        require("tailwindcss-grid")({
            grids: [2, 3, 5, 6, 8, 10, 12],
            gaps: {
                0: "0",
                4: "1rem",
                8: "2rem",
                "4-x": "1rem",
                "4-y": "1rem"
            },
            autoMinWidths: {
                "16": "4rem",
                "24": "6rem",
                "300px": "300px"
            },
            variants: ["responsive"]
        })
    ]
};
