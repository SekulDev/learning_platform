import React from "react";

export function useDarkMode(): [
    boolean,
    React.Dispatch<React.SetStateAction<boolean>>,
] {
    const [darkMode, setDarkMode] = React.useState<boolean>(
        document.body.classList.contains("dark"),
    );

    React.useEffect(() => {
        setDarkMode(window.localStorage.getItem("darkMode") == "true");
    }, []);

    React.useEffect(() => {
        window.localStorage.setItem("darkMode", darkMode.toString());
        document.body.classList.remove("dark", "light");
        document.body.classList.add(darkMode ? "dark" : "light");
    }, [darkMode]);

    return [darkMode, setDarkMode];
}
