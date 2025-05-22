import { useQuery } from "@tanstack/react-query";
import { getAllVoters } from "./services";

export const useFetchVoters = () =>
  useQuery({
    queryKey: ["voters"],
    queryFn: getAllVoters,
  });
