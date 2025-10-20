async function ajaxRequest(url, method = "POST", body = {}, headers = {}) {
    try {
        let defaultHeaders = {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            ...headers
        };

        let requestBody = null;
        if (method !== "GET") {
            if (body instanceof FormData) {
                requestBody = body;
            } else {
                requestBody = JSON.stringify(body);
                defaultHeaders["Content-Type"] = "application/json";
            }
        }

        let response = await fetch(url, {
            method: method,
            headers: defaultHeaders,
            body: requestBody
        });

        const contentType = response.headers.get("content-type");
        if (contentType && contentType.includes("application/json")) {
            return await response.json();
        } else {
            return { error: true, message: "Unexpected response format" };
        }
    } catch (error) {
        return { error: true, message: error.message };
    }
}