import { useMutation } from "@tanstack/react-query";
import { changeStatus, clearVoters, importPDF } from "./services";
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
