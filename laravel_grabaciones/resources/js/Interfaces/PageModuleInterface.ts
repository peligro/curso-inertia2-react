import { ComponentType, ReactElement } from "react";


export interface PageModuleInterface{
    default: ComponentType;
    layout?: (page: ReactElement) => ReactElement;
}