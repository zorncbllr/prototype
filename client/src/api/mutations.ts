import { useMutation } from "@tanstack/react-query";
import { changeStatus, clearVoters, exportFile, importPDF } from "./services";
import { queryClient } from "@/main";
import type { ChangeStatusProps } from "@/types/types";

export const useImportPDF = () => {
  const client = queryClient;

  return useMutation({
    mutationKey: ["voters"],
    mutationFn: async (formData: FormData) => importPDF(formData),

    onSuccess: (data) => {
      client.invalidateQueries({
        queryKey: ["voters"],
      });

      console.log(data);
    },
  });
};

export const useChangeStatus = () => {
  const client = queryClient;

  return useMutation({
    mutationKey: ["voters", "status"],
    mutationFn: async (data: ChangeStatusProps) => changeStatus(data),

    onSuccess: (data) => {
      client.invalidateQueries({
        queryKey: ["voters"],
      });

      console.log(data);
    },
  });
};

export const useClearVoters = () => {
  const client = queryClient;

  return useMutation({
    mutationKey: ["voters", "clear"],
    mutationFn: async () => clearVoters(),

    onSuccess: (data) => {
      client.invalidateQueries({
        queryKey: ["voters"],
      });

      console.log(data);
    },
  });
};

export const useExportFile = () => {
  return useMutation({
    mutationKey: ["voters", "export"],
    mutationFn: async () => exportFile(),

    onSuccess: (data) => {
      const base64String = data.file;
      const binaryString = atob(base64String);
      const binaryLen = binaryString.length;
      const bytes = new Uint8Array(binaryLen);

      for (let i = 0; i < binaryLen; i++) {
        bytes[i] = binaryString.charCodeAt(i);
      }

      const blob = new Blob([bytes], {
        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
      });

      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.setAttribute("download", "voters.xlsx");
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
  });
};
