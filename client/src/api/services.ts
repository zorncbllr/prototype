import { axiosInstance } from "@/lib/api";
import type { ChangeStatusProps, SuccessResponse, Voter } from "@/types/types";

export const getAllVoters = async (): Promise<Voter[]> => {
  return (await axiosInstance.get<Voter[]>("/voters")).data;
};

export const importPDF = async (
  formData: FormData
): Promise<SuccessResponse> => {
  return (
    await axiosInstance.post<SuccessResponse>("/voters/import", formData, {
      headers: {
        "Content-Type": "multipart-formdata",
      },
    })
  ).data;
};

export const changeStatus = async (
  data: ChangeStatusProps
): Promise<SuccessResponse> => {
  return (
    await axiosInstance.patch<SuccessResponse>(`/voters/${data.voterId}`, {
      value: data.value,
    })
  ).data;
};

export const clearVoters = async (): Promise<SuccessResponse> => {
  return (await axiosInstance.delete<SuccessResponse>("/vouters")).data;
};
