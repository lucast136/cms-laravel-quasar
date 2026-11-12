# ==============================================================================
# 1. Builder Stage: Instala dependencias y compila la aplicación en modo SSR
# ==============================================================================
FROM node:24-alpine AS builder

WORKDIR /app

# Instalar dependencias (ignorando scripts de postinstall que requieren la configuración)
COPY package*.json ./
RUN npm install --ignore-scripts

# Copiar el resto del código fuente
COPY . .

# Variables de entorno en tiempo de compilación para Quasar / Vite
ARG VITE_API_URL=http://localhost:8000/api/v1
ENV VITE_API_URL=$VITE_API_URL

# Compilar proyecto en modo SSR (genera carpeta dist/ssr)
RUN npm run build

# ==============================================================================
# 2. Production Runner Stage: Imagen ligera y optimizada para servir SSR
# ==============================================================================
FROM node:24-alpine AS runner

WORKDIR /app

# Copiar los artefactos generados por Quasar SSR
COPY --from=builder /app/dist/ssr ./

# Instalar únicamente dependencias de producción para el servidor SSR
RUN npm install --omit=dev

# Variables de entorno de ejecución
ENV NODE_ENV=production
ENV HOST=0.0.0.0
ENV PORT=3000

EXPOSE 3000

# Iniciar servidor SSR de Quasar (Express)
CMD ["node", "index.js"]
