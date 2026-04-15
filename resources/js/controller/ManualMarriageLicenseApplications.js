import api from "./api";

export async function getManualMarriageLicenseApplications(search = "") {
    const response = await api.get("/manual-applications", {
        params: { search },
    });

    return response;
}

export async function storeManualMarriageLicenseApplication(payload) {
    const response = await api.post("/manual-applications", payload);
    return response;
}

export async function viewManualMarriageLicenseApplication(id) {
    const response = await api.get(`/manual-applications/${id}`);
    return response;
}
