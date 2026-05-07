import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: "#f7f2fb",
                    100: "#efe5f6",
                    200: "#dfcceb",
                    300: "#cfb2e1",
                    400: "#bf99d6",
                    500: "#af80cb",
                    600: "#955fb0",
                    700: "#7f4698",
                    800: "#742f90",
                    900: "#6b2b87",
                },
                secondary: {
                    50: "#fcf3fb",
                    100: "#f8e7f6",
                    200: "#f1cfed",
                    300: "#eab7e3",
                    400: "#e39fda",
                    500: "#dc87d1",
                    600: "#c45eb8",
                    700: "#ad409f",
                    800: "#9c318f",
                    900: "#912786",
                },
                accent: {
                    50: "#fff2fa",
                    100: "#ffe5f5",
                    200: "#ffcceb",
                    300: "#ffb2e0",
                    400: "#ff99d6",
                    500: "#ff80cb",
                    600: "#ff57b9",
                    700: "#ff33aa",
                    800: "#f51b9d",
                    900: "#ea0d94",
                },
            },
        },
    },

    plugins: [forms, typography],
};
