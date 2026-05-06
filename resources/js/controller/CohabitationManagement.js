import api from "./api";

export function listCohabitations(params = {}) {
    return api.get("/cohabitations", { params });
}

export function trashCohabitations(params = {}) {
    return api.get("/cohabitations/trash", { params });
}

export function getCohabitation(id) {
    return api.get(`/cohabitations/${id}`);
}

export function updateCohabitation(id, payload) {
    return api.patch(`/cohabitations/${id}`, payload);
}

export function deleteCohabitation(id) {
    return api.delete(`/cohabitations/${id}`);
}

export function restoreCohabitation(id) {
    return api.patch(`/cohabitations/${id}/restore`);
}

export function forceDeleteCohabitation(id) {
    return api.delete(`/cohabitations/${id}/force`);
}
