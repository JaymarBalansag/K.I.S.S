import api from "./api";

export function listCohabitations(params = {}) {
    return api.get("/cohabitations", { params });
}

export function getCohabitation(id) {
    return api.get(`/cohabitations/${id}`);
}

export function updateCohabitation(id, payload) {
    return api.patch(`/cohabitations/${id}`, payload);
}

