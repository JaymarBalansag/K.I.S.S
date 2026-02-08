import api from "./api";


export async function submitMarriageLicenseApplication(formData) {
    const response = await api.post('/submit/marriage-license-application', formData);
    return response;
}