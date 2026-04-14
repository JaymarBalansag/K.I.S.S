import api from "./api";

export async function readSmsRequests(params = {}) {
    return api.get("/sms-requests", { params });
}

export async function showSmsRequest(id) {
    return api.get(`/sms-requests/${id}`);
}

export async function createSmsRequest(form) {
    return api.post("/sms-requests", form);
}

export async function updateSmsRequest(id, form) {
    return api.put(`/sms-requests/${id}`, form);
}

export async function deleteSmsRequest(id) {
    return api.delete(`/sms-requests/${id}`);
}
