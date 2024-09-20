window.generateShortCode = async function () {
    const now = Date.now();
    const shortCode = now.toString(36);
    return shortCode.slice(-5).toUpperCase();
}
