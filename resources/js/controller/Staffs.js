import api from "./api";

export async function CreateStaff(form) {
    const response = await api.post("/Staffs", form);
}

export async function ReadStaff(){
    const response = await api.get("/Staffs");
    return response;
}

export async function ShowStaff(id) {
    const response = await api.get(`/Staffs/${id}`);
    return response;
}

export async function UpdateStaff(id, form){
    const response = await api.put(`/Staffs/${id}`, form)

}

export async function DeleteStaff(id) {
    const response = await api.delete(`/Staffs/${id}`)
}