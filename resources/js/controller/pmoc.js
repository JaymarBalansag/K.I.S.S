import api from "./api";

export async function CreateAppointment(form) {
    const response = await api.post("/Appointments", form);
    return response;
}

export async function ReadAppointment() {
    const response = await api.get("/Appointments");
    return response;
}

export async function ShowAppointment(id) {
    const response = await api.get(`/Appointments/${id}`);
    return response;
}

export async function UpdateAppointment(id, form) {
    const response = await api.put(`/Appointments/${id}`, form);
    return response;
}

export async function DeleteAppointment(id) {
    const response = await api.delete(`/Appointments/${id}`);
    return response;
}
